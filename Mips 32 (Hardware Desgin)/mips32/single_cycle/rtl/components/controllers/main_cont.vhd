--                                      CONTROLLER_TABLE                                                --
-- op_code          | reg_dst |jump |branch |mem_read |mem_to_reg |alu_op |mem_write |alu_src |reg_write
-- 000000  (r_types)|    1      0      0        0           0      op_code    0         0         1                                                     
-- 000001   andi    |    0      0      0        0           0      op_code    0         1         1                
-- 000010   ori     |    0      0      0        0           0      op_code    0         1         1                
-- 000011   addi    |    0      0      0        0           0      op_code    0         1         1                
-- 000100   subi    |    0      0      0        0           0      op_code    0         1         1                
-- 000101   slti    |    0      0      0        0           0      op_code    0         1         1                
-- 000110   nori    |    0      0      0        0           0      op_code    0         1         1                
-- 000111   lw      |    0      0      0        1           1      op_code    0         1         1                
-- 001000   sw      |    0      0      0        0           0      op_code    1         1         0 
-- 001001   beq     |    0      0      1        0           0      op_code    0         0         0              
-- 001010   bne     |    0      0      1        0           0      op_code    0         0         0                
-- 001011   j       |    0      1      0        0           0      op_code    0         0         0    

library ieee;
use ieee.std_logic_1164.all;
use ieee.numeric_std.all;
entity main_control is
    port(
        op_code : in std_logic_vector(5 downto 0) := (others => '0');
        ctrls : out std_logic_vector(13 downto 0)  
    );
end entity;

architecture rtl of main_control is
begin 
    process(op_code)     
    begin
        case op_code is
            when "000000" => 
                ctrls <= "10000" & op_code & "001";
            when "000001" => 
                ctrls <= "00000" & op_code & "011";
            when "000010" => 
                ctrls <= "00000" & op_code & "011";
                
            when "000011" => 
                ctrls <= "00000" & op_code & "011";
                
            when "000100" => 
                ctrls <= "00000" & op_code & "011";
                
            when "000101" => 
                ctrls <= "00000" & op_code & "011";
                
            when "000110" => 
                ctrls <= "00000" & op_code & "011";
                
            when "000111" => 
                ctrls <= "00011" & op_code & "011";
                
            when "001000" => 
                ctrls <= "00000" & op_code & "110";
                
            when "001001" => 
                ctrls <= "00100" & op_code & "000";
                
            when "001010" => 
                ctrls <= "00100" & op_code & "000";
                
            when "001011" =>
                ctrls <= "01000" & op_code & "000";

            when others => null;
                ctrls <= (others => '0');
        end case;
    end process;
end architecture;